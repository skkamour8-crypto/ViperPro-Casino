<template>
    <BaseLayout>
        <div class="mx-auto mt-20 max-w-2xl p-6">
            <div class="rounded-lg bg-gray-100 p-6 text-center dark:bg-gray-800">
                <i class="fa-solid fa-circle-info mb-3 text-3xl"></i>
                <h1 class="mb-2 text-xl font-bold">Points-only mode</h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Deposits and withdrawals are disabled. Accounts use virtual points only, with no cash value and no redemption.
                </p>
                <button @click="$router.push('/profile/wallet')" class="ui-button-blue mt-5 rounded px-5 py-2">
                    View points balance
                </button>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@/Layouts/BaseLayout.vue";

export default {
    components: { BaseLayout },
};
</script>
