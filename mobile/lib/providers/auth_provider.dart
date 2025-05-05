import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
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
} 