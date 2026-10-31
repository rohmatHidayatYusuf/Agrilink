  <!-- Scripts -->
  <script>
    // Toggle Password Visibility
    document.querySelectorAll('.password-toggle-btn').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        if (!input) return;

        const isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');

        // Ganti icon
        const eyeIcon = this.querySelector('.icon-eye');
        const eyeOffIcon = this.querySelector('.icon-eye-off');
        if (eyeIcon && eyeOffIcon) {
          eyeIcon.style.display = isPassword ? 'none' : 'block';
          eyeOffIcon.style.display = isPassword ? 'block' : 'none';
        }
      });
    });

    // Helper untuk auto-fill demo form login jika diklik
    function fillLogin(identifier, password) {
      const idInput = document.getElementById('identifier');
      const passInput = document.getElementById('password');
      if (idInput && passInput) {
        idInput.value = identifier;
        passInput.value = password;
        idInput.focus();
      }
    }
  </script>
</body>
</html>