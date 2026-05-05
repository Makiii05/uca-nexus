<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof showToast !== 'function') return;

        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast(@json($error), 'error');
            @endforeach
        @endif
    });
</script>
