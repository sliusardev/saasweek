<script setup lang="ts">
import axios from 'axios';
import { Button } from '@/components/ui/button';

const loginWithProvider = async (provider: string) => {
    try {
        const response = await axios.get(route('socialite.auth.redirect', provider));
        if (response.data) {
            window.location.href = response.data;
        }
    } catch (error) {
        console.error(`${provider} auth error:`, error);
    } finally {

    }
};
</script>

<template>
    <div class="flex flex-wrap gap-2 mb-6 justify-center">
        <Button type="button" class="w-1/3 flex items-center justify-center gap-2 border-black"
                :variant="'outline'"
                @click="loginWithProvider('google')"
        >
            Google
        </Button>
        <Button type="button" class="w-1/3 flex items-center justify-center gap-2 border-black"
                :variant="'outline'"
                @click="loginWithProvider('github')"
        >
           GitHub
        </Button>
    </div>
</template>
