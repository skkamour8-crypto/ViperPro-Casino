<template>
    <BaseLayout>
        <div class="md:w-4/6 2xl:w-4/6 mx-auto mt-20 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-1 hidden md:block">
                    <WalletSideMenu />
                </div>
                <section class="col-span-2 rounded-lg bg-gray-200 dark:bg-gray-800/70 p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-full border-2 border-gray-300 dark:border-gray-600 p-4">
                            <i class="fa-solid fa-star text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Virtual points balance</p>
                            <h1 class="text-3xl font-bold text-yellow-400">
                                {{ Number(wallet?.total_balance || 0).toLocaleString() }} points
                            </h1>
                        </div>
                    </div>
                    <p class="mt-6 text-sm text-gray-600 dark:text-gray-300">
                        Points are for entertainment only. They have no cash value and cannot be deposited, withdrawn, transferred, or redeemed.
                    </p>
                    <div v-if="isLoadingWallet" class="mt-5 text-sm">{{ $t('Loading') }}...</div>
                    <div v-else-if="!wallet" class="mt-5 text-sm">Your points wallet is not available yet.</div>
                </section>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import WalletSideMenu from "@/Pages/Profile/Components/WalletSideMenu.vue";
import HttpApi from "@/Services/HttpApi.js";
import { useToast } from "vue-toastification";

export default {
    components: { BaseLayout, WalletSideMenu },
    data() {
        return { wallet: null, isLoadingWallet: true };
    },
    created() {
        this.getWallet();
    },
    methods: {
        async getWallet() {
            this.isLoadingWallet = true;
            try {
                const response = await HttpApi.get("profile/wallet");
                this.wallet = response.data.wallet;
            } catch (error) {
                useToast().error("Could not load points balance.");
            } finally {
                this.isLoadingWallet = false;
            }
        },
    },
};
</script>
