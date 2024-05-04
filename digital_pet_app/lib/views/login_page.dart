import 'package:flutter/material.dart';
import 'package:digital_pet_app/services/authentication_service.dart';
import 'package:digital_pet_app/services/digital_monster_service.dart';
import 'package:digital_pet_app/utils/secure_storage.dart';
import 'package:digital_pet_app/views/digital_monster_screen.dart'; // Import the digital monster screen

class LoginPage extends StatelessWidget {
  final GlobalKey<ScaffoldMessengerState> scaffoldKey = GlobalKey();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final AuthenticationService authService = AuthenticationService();
  final DigitalMonsterService monsterService = DigitalMonsterService();
  final SecureStorage storage = SecureStorage();

  LoginPage({super.key});

  @override
  Widget build(BuildContext context) {
    return ScaffoldMessenger(
      key: scaffoldKey,
      child: Scaffold(
        appBar: AppBar(title: const Text('Login')),
        body: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              TextField(
                controller: emailController,
                decoration: const InputDecoration(labelText: 'Email'),
              ),
              const SizedBox(height: 16),
              TextField(
                controller: passwordController,
                decoration: const InputDecoration(labelText: 'Password'),
                obscureText: true,
              ),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: () => login(context),
                child: const Text('Login'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> login(BuildContext context) async {
    final String email = emailController.text;
    final String password = passwordController.text;

    try {
      final String? token = await authService.login(email, password);
      if (token != null) {

        // Store token securely
        await storage.saveToken(token);

        // Navigate to the Digital Monster Screen
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const DigitalMonsterScreen()),
        );
      } else {
        scaffoldKey.currentState?.showSnackBar(
          const SnackBar(content: Text('Login failed')),
        );
      }
    } catch (e) {
      scaffoldKey.currentState?.showSnackBar(
        const SnackBar(content: Text('An error occurred while logging in')),
      );
    }
  }
}
