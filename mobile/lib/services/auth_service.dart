import 'dart:convert';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';

class AuthService {
  static const String baseUrl = ApiConfig.baseUrl;

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
    String? address,
  }) async {
    final url = Uri.parse('$baseUrl/api/customer/register');
    print('Register URL: $url'); // Debug log

    final body = {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'phone': phone,
      'address': address,
    };
    print('Register body: $body'); // Debug log

    try {
      final response = await http.post(
        url,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Access-Control-Allow-Origin': '*',
        },
        body: jsonEncode(body),
      );

      print('Register response status: ${response.statusCode}'); // Debug log
      print('Register response body: ${response.body}'); // Debug log

      if (response.statusCode == 201) {
        return jsonDecode(response.body);
      } else {
        final errorBody = jsonDecode(response.body);
        throw Exception(errorBody['message'] ?? 'Failed to register');
      }
    } catch (e) {
      print('Register error: $e'); // Debug log
      rethrow;
    }
  }

  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final url = Uri.parse('$baseUrl/api/customer/login');
    print('Login URL: $url'); // Debug log

    try {
      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      print('Login response status: ${response.statusCode}'); // Debug log
      print('Login response body: ${response.body}'); // Debug log

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else {
        throw Exception('Failed to login: ${response.body}');
      }
    } catch (e) {
      print('Login error: $e'); // Debug log
      rethrow;
    }
  }

  Future<void> logout(String token) async {
    final url = Uri.parse('$baseUrl/api/customer/logout');
    print('Logout URL: $url'); // Debug log

    try {
      final response = await http.post(
        url,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Logout response status: ${response.statusCode}'); // Debug log
      print('Logout response body: ${response.body}'); // Debug log

      if (response.statusCode != 200) {
        throw Exception('Failed to logout: ${response.body}');
      }
    } catch (e) {
      print('Logout error: $e'); // Debug log
      rethrow;
    }
  }

  Future<Map<String, dynamic>> getProfile(String token) async {
    final url = Uri.parse('$baseUrl/api/customer/profile');
    print('Get profile URL: $url'); // Debug log

    try {
      final response = await http.get(
        url,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Get profile response status: ${response.statusCode}'); // Debug log
      print('Get profile response body: ${response.body}'); // Debug log

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else {
        throw Exception('Failed to get profile: ${response.body}');
      }
    } catch (e) {
      print('Get profile error: $e'); // Debug log
      rethrow;
    }
  }
} 