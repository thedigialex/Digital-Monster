import 'package:flutter/material.dart';
import 'package:digital_pet_app/utils/secure_storage.dart';
import 'package:digital_pet_app/services/digital_monster_service.dart';

class DigitalMonsterScreen extends StatefulWidget {
  const DigitalMonsterScreen({super.key});

  @override
  DigitalMonsterScreenState createState() => DigitalMonsterScreenState();
}

class DigitalMonsterScreenState extends State<DigitalMonsterScreen> {
  final SecureStorage storage = SecureStorage();
  final DigitalMonsterService monsterService = DigitalMonsterService();
  String monsterName = "Loading...";

  @override
  void initState() {
    super.initState();
    loadMonsterDetails();
  }

  void loadMonsterDetails() async {
    String? token = await storage.getToken();
    if (token != null) {
      String? name = await monsterService.fetchDigitalMonster(token);
      if (mounted) {
        if (name != null) {
          setState(() {
            monsterName = name;
          });
        } else {
          setState(() {
            monsterName = "No Digital Monster found";
          });
        }
      }
    } else if (mounted) {
      setState(() {
        monsterName = "Authentication required";
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Digital Monster Details'),
      ),
      body: Center(
        child: Text(monsterName, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
      ),
    );
  }
}
