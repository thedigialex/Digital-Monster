import 'dart:convert';
import 'package:http/http.dart' as http;

class DigitalMonsterService {
  Future<String?> fetchDigitalMonster(String token) async {
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
        return "Digital Monster: ${data[0]['name']}";
      } else {
        return "Digital Monster not found";
      }
    } else {
      return "Failed to fetch Digital Monster";
    }
  }
}
