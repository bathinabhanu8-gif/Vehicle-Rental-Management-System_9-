<?php
session_name('VS_CUSTOMER');session_start();

if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'user') {
    header('Location: dashboard.php');
    exit;
}


require_once 'includes/header.php';
?>

<section class="auth-page">

    <div class="auth-image register-image">
        <div>
            <span class="eyebrow">JOIN VS TRAVELLS</span>
            <h1>Your next drive is closer than you think.</h1>
            <p>Create your customer profile and start requesting vehicles.</p>
        </div>
    </div>

    <div class="auth-panel">

        <form id="regForm" class="auth-form">

            <span class="eyebrow dark">CREATE ACCOUNT</span>

            <h2>Start your journey</h2>

            <div id="msg"></div>

            <label>
                Full name
                <input
                    id="name"
                    type="text"
                    required
                    placeholder="Your full name"
                >
            </label>

            <label>
                Email address
                <input
                    type="email"
                    id="email"
                    required
                    placeholder="you@example.com"
                >
            </label>

            <label>
                Phone number
                <input
                    id="phone"
                    type="tel"
                    required
                    placeholder="10-digit mobile number"
                >
            </label>

            <label>
                Password
                <input
                    type="password"
                    id="password"
                    minlength="6"
                    required
                    placeholder="Minimum 6 characters"
                >
            </label>

            <button type="submit" class="btn wide">
                Create account
            </button>

            <p class="auth-switch">
                Already registered?
                <a href="login.php">Sign in</a>
            </p>

        </form>

    </div>

</section>

<script>
const regForm = document.getElementById('regForm');
const msg = document.getElementById('msg');

regForm.addEventListener('submit', async function(e) {

    e.preventDefault();

    const fullName = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const password = document.getElementById('password').value;

    if (!fullName) {
        msg.innerHTML =
            '<div class="message error">Please enter your full name.</div>';
        return;
    }

    try {

        const response = await fetch('api/auth/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: fullName,
                email: email,
                phone: phone,
                password: password
            })
        });

        const result = await response.json();

        msg.innerHTML =
            `<div class="message ${result.success ? 'success' : 'error'}">
                ${result.message}
            </div>`;

        if (result.success) {
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 800);
        }

    } catch (error) {

        msg.innerHTML =
            '<div class="message error">Unable to create account. Please try again.</div>';

    }

});
</script>

<?php require_once 'includes/footer.php'; ?>