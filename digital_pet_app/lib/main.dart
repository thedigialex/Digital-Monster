import 'package:flutter/material.dart';
import 'views/login_page.dart';
import 'views/digital_monster_screen.dart';
import 'utils/secure_storage.dart';

void main() => runApp(MyApp());

class MyApp extends StatelessWidget {
  final SecureStorage storage = SecureStorage();

  MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Digital Monster App',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: FutureBuilder<String?>(
        future: storage.getToken(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Scaffold(
              body: Center(child: CircularProgressIndicator()),
            );
          }
          if (snapshot.hasError) {
            // Consider handling errors if any
            return Scaffold(
              body: Center(child: Text("Error: ${snapshot.error}")),
            );
          }
          if (snapshot.hasData && snapshot.data!.isNotEmpty) {
            return DigitalMonsterScreen(); // Assuming token is valid
          }
          return LoginPage();
        },
      ),
    );
  }
}
