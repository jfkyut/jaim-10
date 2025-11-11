import axios from "axios";

export const useFollow = (user) => {
    const follow = async () => {
        try {
            const { data } = await axios.post(route('user.follow', user.id));
            
            return {
                followers_count: data.followers_count,
                is_following: data.is_following
            }
        } catch (error) {
            console.error('Error following user:', error);
        }
    };

    const unfollow = async () => {
        try {
            const { data } = await axios.post(route('user.unfollow', user.id));
            
            return {
                followers_count: data.followers_count,
                is_following: data.is_following
            }
        } catch (error) {
            console.error('Error unfollowing user:', error);
        } 
    };

    return {
        follow,
        unfollow
    };
}