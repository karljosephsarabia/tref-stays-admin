<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Admin Login</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 100px auto; padding: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 12px; background: #1f2937; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #374151; }
        .error { color: red; padding: 10px; background: #fee; margin: 10px 0; border-radius: 4px; }
        .success { color: green; padding: 10px; background: #dfd; margin: 10px 0; border-radius: 4px; }
        .info { padding: 10px; background: #f0f0f0; margin: 20px 0; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
    <h2>Admin Login</h2>
    
    <div class="info">
        <strong>Correct Credentials:</strong><br>
        Email: <code>admin@ivrreservation.com</code><br>
        Password: <code>admin123456</code><br>
        <small>(Note: TWO "r" in "reservation")</small>
    </div>
    
    <?php
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        require __DIR__.'/vendor/autoload.php';
        $app = require_once __DIR__.'/bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        
        use App\RsUser;
        use Illuminate\Support\Facades\Hash;
        use Illuminate\Support\Facades\Auth;
        
        $user = RsUser::where('email', $email)->first();
        
        if ($user && Hash::check($password, $user->password) && $user->role_id === 'admin' && $user->activated) {
            Auth::login($user);
            header('Location: /admin/dashboard');
            exit;
        } else {
            $error = "Login failed. ";
            if (!$user) {
                $error .= "User with email '$email' not found. ";
            } elseif (!Hash::check($password, $user->password)) {
                $error .= "Password incorrect. ";
            } elseif ($user->role_id !== 'admin') {
                $error .= "User is not an admin (role: {$user->role_id}). ";
            } elseif (!$user->activated) {
                $error .= "Account not activated. ";
            }
            echo "<div class='error'>$error</div>";
        }
    }
    ?>
    
    <form method="POST">
        <input type="email" name="email" placeholder="Email Address" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
    </form>
    
    <p style="text-align:center; margin-top:20px;">
        <a href="/admin/login">Use Standard Login Page</a>
    </p>
</body>
</html>