<?php
$redirectAction = $redirectAction ?? \App\Support\SessionManager::redirectAfterLogin();
$providers = [
    ['key' => 'google', 'label' => 'Google', 'tone' => 'border-red-100 text-red-600 hover:bg-red-50'],
    ['key' => 'facebook', 'label' => 'Facebook', 'tone' => 'border-blue-100 text-blue-600 hover:bg-blue-50'],
    ['key' => 'github', 'label' => 'GitHub', 'tone' => 'border-gray-200 text-gray-800 hover:bg-gray-50'],
];
ob_start();
?>

<section class="min-h-[80vh] flex items-center justify-center p-6 bg-gray-50">
    <div id="authApp" class="bg-white rounded-[2rem] shadow-2xl p-8 md:p-10 max-w-md w-full border border-gray-100 relative overflow-hidden" v-cloak>
        <div class="absolute top-0 left-0 w-full h-2 bg-[#1a4731]"></div>

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 uppercase">Bienvenido</h2>
            <p class="text-gray-500 text-sm mt-2">Accede a tu cuenta gourmet.</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-sm font-bold border border-red-100">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-3 mb-6">
            <a
                v-for="provider in providers"
                :key="provider.key"
                :href="provider.url"
                :class="'w-full border rounded-2xl px-4 py-3 text-sm font-bold transition flex items-center justify-center gap-3 ' + provider.tone"
            >
                <span class="h-7 w-7 rounded-full bg-white border border-current/10 flex items-center justify-center text-xs uppercase">
                    {{ provider.label.charAt(0) }}
                </span>
                Continuar con {{ provider.label }}
            </a>
        </div>

        <div class="flex items-center gap-4 mb-6">
            <div class="h-px bg-gray-200 flex-1"></div>
            <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">o con correo</span>
            <div class="h-px bg-gray-200 flex-1"></div>
        </div>

        <form action="index.php?action=login" method="POST" class="space-y-5">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirectAction); ?>">

            <div>
                <label class="form-label">Correo Electronico</label>
                <input type="email" name="email" required placeholder="email@ejemplo.com" class="input-biconoir">
            </div>

            <div>
                <label class="form-label">Contrasena</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="********" class="input-biconoir pr-20">
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-[#1a4731]">
                        {{ showPassword ? 'Ocultar' : 'Ver' }}
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full btn-primary !py-4">Entrar al sistema</button>

            <div class="text-center pt-3">
                <p class="text-sm text-gray-500">
                    Eres nuevo?
                    <a href="index.php?action=register" class="text-[#1a4731] font-bold hover:underline">Registrate aqui</a>
                </p>
            </div>
        </form>
    </div>
</section>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
    const authProviders = <?php echo json_encode(array_map(static function ($provider) use ($redirectAction) {
        return [
            'key' => $provider['key'],
            'label' => $provider['label'],
            'tone' => $provider['tone'],
            'url' => 'index.php?action=oauth_redirect&provider=' . urlencode($provider['key']) . '&redirect=' . urlencode($redirectAction),
        ];
    }, $providers)); ?>;

    Vue.createApp({
        data() {
            return {
                providers: authProviders,
                showPassword: false
            };
        }
    }).mount('#authApp');
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
