<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>NotifySMS — Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Inter',sans-serif;}
#login-screen{position:fixed;inset:0;background:linear-gradient(135deg,#001260,#0033b0,#0055d4);display:flex;align-items:center;justify-content:center;padding:20px;}
.login-box{background:white;border-radius:24px;padding:48px 40px;width:100%;max-width:400px;box-shadow:0 32px 80px rgba(0,0,0,.4);}
.login-logo{display:flex;align-items:center;gap:10px;justify-content:center;margin-bottom:32px;}
.login-logo-icon{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#009cde,#003087);display:flex;align-items:center;justify-content:center;}
.login-logo-text{font-size:22px;font-weight:800;color:#003087;}
.login-box h2{font-size:20px;font-weight:800;color:#003087;text-align:center;margin-bottom:6px;}
.login-box p{font-size:13px;color:#64748b;text-align:center;margin-bottom:28px;}
.login-field{margin-bottom:16px;}
.login-field label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#64748b;display:block;margin-bottom:6px;}
.login-field input{width:100%;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 14px;font-size:14px;font-family:inherit;outline:none;transition:border-color .2s;}
.login-field input:focus{border-color:#003087;}
.login-remember{display:flex;align-items:center;gap:8px;font-size:13px;color:#64748b;margin-bottom:8px;}
.login-remember input{width:auto;}
.login-btn{width:100%;background:#003087;color:white;border:none;border-radius:12px;padding:13px;font-size:15px;font-weight:700;cursor:pointer;margin-top:8px;transition:all .2s;}
.login-btn:hover{background:#0044bb;}
.login-err{background:#fee2e2;color:#dc2626;font-size:12px;border-radius:8px;padding:8px 12px;margin-bottom:12px;}
</style>
</head>
<body>
<div id="login-screen">
  <div class="login-box">
    <div class="login-logo">
      <div class="login-logo-icon"><i class="fas fa-comment-sms text-white" style="color:#fff;font-size:20px;"></i></div>
      <div class="login-logo-text">Notify<span style="color:#009cde;">SMS</span></div>
    </div>
    <h2>Admin Portal</h2>
    <p>Sign in to manage your website content</p>

    @if ($errors->any())
      <div class="login-err"><i class="fas fa-circle-exclamation"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.attempt') }}">
      @csrf
      <div class="login-field">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@notifysms.com.bd" autofocus required>
      </div>
      <div class="login-field">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>
      </div>
      <label class="login-remember">
        <input type="checkbox" name="remember" value="1"> Remember me
      </label>
      <button type="submit" class="login-btn"><i class="fas fa-sign-in-alt" style="margin-right:8px;"></i>Sign In</button>
    </form>
  </div>
</div>
</body>
</html>
