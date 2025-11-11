<script setup>
import { onMounted, onBeforeUnmount, nextTick } from 'vue';

const pushAd = () => {
  nextTick(() => {
    try { (window.adsbygoogle = window.adsbygoogle || []).push({}); }
    catch (e) { /* ignore init errors */ }
  });
};

const onInertiaFinish = () => pushAd();

onMounted(() => {
  pushAd();
  document.addEventListener('inertia:finish', onInertiaFinish);
});

onBeforeUnmount(() => {
  document.removeEventListener('inertia:finish', onInertiaFinish);
});
</script>

<template>
  <div class="min-h-[120px] w-full flex justify-center items-center" aria-hidden="true">
    <ins class="adsbygoogle block w-full"
        style="display:block"
        data-ad-client="ca-pub-4833227951148257"
        data-ad-slot="8385383368"
        data-ad-format="auto"
        data-full-width-responsive="true"></ins>
  </div>
</template>