import 'dart:convert';
import 'package:http/http.dart' as http;

class AuthenticationService {
  Future<String?> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('http://10.0.2.2:8000/api/auth/login'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        'email': email,
        'password': password,
      }),
    );
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['token'];
    } else {
      return null;
    }
  }
}

