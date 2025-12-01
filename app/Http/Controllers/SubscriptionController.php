<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::where('user_id', auth()->id())
                                    ->with('plan')
                                    ->latest()
                                    ->paginate(100);

        return inertia('SubscriptionPlan/SubscriptionHistory', [
            'subscriptions' => $subscriptions
        ]);
    }

    public function create()
    {
        $plans = Plan::all();
        $currentPlan = auth()->user()->activePlan();

        foreach ($plans as $plan) {
            $plan->features = json_decode($plan->features);
        }

        return inertia('SubscriptionPlan/SubscriptionPlan', [
            'plans' => $plans,
            'currentPlan' => $currentPlan
        ]);
    }

    public function subscribe(Request $request, Plan $plan)
    {
        $response = Http::withHeaders([
                            'accept' => 'application/json',
                            'authorization' => 'Basic ' . base64_encode(env('PAYMONGO_SECRET_KEY')),
                            'content-type' => 'application/json',
                        ])
                        ->post(
                            'https://api.paymongo.com/v1/checkout_sessions',
                            [
                                'data' => [
                                    'id' => 'cs_CbFCTDfxvMFNjwjVi26Uzhtj',
                                    'type' => 'checkout_session',
                                    'attributes' => [
                                        'merchant' => 'JAM Subscription Service',
                                        'success_url' => route('subscription.success'),
                                        'cancel_url' => route('subscription.index'),
                                        'billing' => [
                                            'address' => [
                                                'city' => 'Taguig',
                                                'country' => 'PH', 
                                                'line1' => 'address line 1',
                                                'line2' => 'address line 2',
                                                'postal_code' => '1234',
                                                'state' => 'PH-MNL'
                                            ],
                                            'email' => $request->user()->email,
                                            'name' => $request->user()->first_name . ' ' . $request->user()->last_name,
                                            'phone' => null
                                        ],
                                        'description' => json_decode($plan->features),
                                        'line_items' => [
                                            [
                                                'amount' => $plan->price * 100,
                                                'currency' => 'PHP',
                                                'description' => json_decode($plan->features),
                                                // 'images' => [
                                                //     'https://images.unsplash.com/photo-1612346903007-b5ac8bb135bb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80'
                                                // ],
                                                'name' => $plan->name,
                                                'quantity' => 1
                                            ],
                                        ],
                                        'livemode' => true,
                                        'payment_intent' => [
                                            'id' => 'pi_aJsHfCD2AmR9V5KBvtkW8XY2',
                                            'type' => 'payment_intent',
                                            'attributes' => [
                                                'currency' => 'PHP',
                                                'description' => explode(', ', $plan->features),
                                                'status' => 'succeeded',
                                                'statement_descriptor' => 'Paymongo Test Account',

                                            ]
                                        ],
                                        'payment_method_types' => ['card', 'gcash',],
                                        'reference_number' => 'm2m39sj43h5lfFSA1sd',
                                        'status' => 'active',
                                    ]
                                ]
                            ]
                        );

        Session::put([
            'selected_plan_id' => $plan->id,
        ]);

        return inertia('SubscriptionPlan/CheckoutPage', [
            'redirectUrl' => $response->json()['data']['attributes']['checkout_url']
        ]);
    }

    public function success(Request $request)
    {
        // Create subscription record with 1-month duration
        // Note: When this subscription expires (ends_at < now()), 
        // the daily scheduler (CheckExpiredSubscriptions) will automatically 
        // revert the user's role to 'listener'
        Subscription::create([
            'user_id' => auth()->id(),
            'plan_id' => Session::get('selected_plan_id'),
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),  // Subscription valid for 1 month
            'status' => 'active'
        ]);

        // Upgrade user role to 'creator' (role_id = 2) if not already admin
        if ($request->user()->role_id !== 3) {
            $request->user()->role_id = 2;
            $request->user()->save();
        }

        return redirect(route('subscription.index'));
    }
}
