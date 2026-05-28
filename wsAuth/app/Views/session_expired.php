<?php ob_start(); ?>

<section class="min-h-[80vh] flex items-center justify-center p-6 bg-gray-50">
    <div class="bg-white rounded-[2rem] shadow-2xl p-10 max-w-md w-full border border-gray-100 text-center">
        <div class="mx-auto mb-6 h-14 w-14 rounded-full bg-[#1a4731] text-white flex items-center justify-center text-2xl font-bold">
            !
        </div>
        <h2 class="text-3xl font-bold text-gray-800 uppercase">Ingresar nuevamente</h2>
        <p class="text-gray-500 mt-3">
            Tu sesion ya no esta activa. Por seguridad debes iniciar sesion otra vez.
        </p>
        <a href="index.php?action=login&redirect=<?php echo urlencode($redirectAction ?? 'home'); ?>" class="inline-flex items-center justify-center mt-8 w-full btn-primary !py-4">
            Ir al login
        </a>
    </div>
</section>

<script>
    window.setTimeout(function () {
        window.location.href = 'index.php?action=login&redirect=<?php echo urlencode($redirectAction ?? 'home'); ?>';
    }, 2500);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
