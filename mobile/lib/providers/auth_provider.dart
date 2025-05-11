import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import '../services/auth_service.dart';

class AuthProvider with ChangeNotifier {
  String? _token;
  Map<String, dynamic>? _user;

  String? get token => _token;
  Map<String, dynamic>? get user => _user;
  bool get isAuthenticated => _token != null;

  final AuthService _authService = AuthService();
  final SharedPreferences _prefs;

  AuthProvider(this._prefs) {
    _loadToken();
  }

  Future<void> _loadToken() async {
    _token = _prefs.getString('auth_token');
    if (_token != null) {
      await _loadUser();
    }
    notifyListeners();
  }

  Future<void> _loadUser() async {
    try {
      final response = await _authService.getProfile(_token!);
      _user = response['data']['customer'];
    } catch (e) {
      _token = null;
      _user = null;
      await _prefs.remove('auth_token');
    }
  }

  Future<void> login(String email, String password) async {
    try {
      final response = await _authService.login(
        email: email,
        password: password,
      );
      
      _token = response['data']['token'];
      _user = response['data']['customer'];
      
      await _prefs.setString('auth_token', _token!);
      notifyListeners();
    } catch (e) {
      rethrow;
    }
  }

  Future<void> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
    String? address,
  }) async {
    try {
      final response = await _authService.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
        phone: phone,
        address: address,
      );
      
      _token = response['data']['token'];
      _user = response['data']['customer'];
      
      await _prefs.setString('auth_token', _token!);
      notifyListeners();
    } catch (e) {
      rethrow;
    }
  }

  Future<void> logout() async {
    try {
      if (_token != null) {
        await _authService.logout(_token!);
      }
    } finally {
      _token = null;
      _user = null;
      await _prefs.remove('auth_token');
      notifyListeners();
    }
  }

  // Add these to your AuthProvider class
  List<Map<String, dynamic>> _userOrders = [];
  List<Map<String, dynamic>> get userOrders => _userOrders;
  
  Future<void> fetchUserOrders() async {
    try {
      // Strict check for authenticated user with valid ID
      if (!isAuthenticated || user?['id'] == null) {
        _userOrders = []; // Clear any existing orders
        notifyListeners();
        return;
      }

      final response = await http.get(
        Uri.parse('http://127.0.0.1:8000/api/customers/${user!['id']}/orders'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        // Ensure we only process orders for the current user
        if (responseData['status'] == 'success') {
          _userOrders = List<Map<String, dynamic>>.from(responseData['data']
              .where((order) => order['customer_id'] == user!['id']));
          notifyListeners();
        }
      } else if (response.statusCode == 404) {
        _userOrders = []; // Clear orders if none found
        notifyListeners();
      }
    } catch (error) {
      debugPrint('Order fetch error: $error');
      rethrow;
    }
  }
}