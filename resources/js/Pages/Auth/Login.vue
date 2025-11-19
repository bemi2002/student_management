<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="min-h-screen flex items-center justify-center" style="background: linear-gradient(to bottom, #001f4d, #003366);">
            <div class="w-full max-w-md bg-white/95 backdrop-blur-md rounded-xl shadow-xl p-8 animate-fade-in">
                
                <!-- Logo and title -->
                <div class="flex flex-col items-center mb-6">
                    <img
                        src="https://tse2.mm.bing.net/th/id/OIP.KjNeiFwKrH5nCPb_MtehQAHaEK?rs=1&pid=ImgDetMain&o=7&rm=3"
                        alt="Student Management System Logo"
                        class="w-32 h-auto mb-4"
                    />
                    <h2 class="text-2xl font-bold text-[#001f4d] text-center">Student Management System</h2>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="mb-4 text-sm font-medium text-red-600 text-center">
                    {{ status }}
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="email" value="Email" class="text-[#001f4d]" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full border-[#001f4d] focus:border-[#003366] focus:ring-[#003366]"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" class="text-[#001f4d]" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full border-[#001f4d] focus:border-[#003366] focus:ring-[#003366]"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-[#001f4d]">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-[#003366] underline hover:text-[#001f4d] focus:outline-none focus:ring-2 focus:ring-[#003366] focus:ring-offset-2 text-sm"
                        >
                            Forgot your password?
                        </Link>
                    </div>

                    <div>
                        <PrimaryButton
                            class="w-full mt-2 bg-[#001f4d] hover:bg-[#003366] text-white"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Log in
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>

<style>
.animate-fade-in {
    animation: fadeIn 1.5s ease-in-out;
}

@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}
</style>
