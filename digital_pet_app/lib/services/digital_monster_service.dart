import 'dart:convert';
import 'package:http/http.dart' as http;

import '/models/digital_monster.dart';

class DigitalMonsterService {
  Future<DigitalMonster?> fetchDigitalMonster(String token) async {
    final response = await http.get(
      Uri.parse('http://10.0.2.2:8000/api/digital-monsters'),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data.isNotEmpty) {
        return DigitalMonster.fromJson(data[0]);
      } else {
        return null; // Or throw an exception if you prefer
      }
    } else {
      throw Exception('Failed to fetch Digital Monster'); // Handling the error
    }
  }
}
