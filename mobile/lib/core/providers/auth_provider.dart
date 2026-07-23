import 'package:flutter/material.dart';
import '../services/auth_service.dart';
import '../services/storage_service.dart';

class AuthProvider extends ChangeNotifier {
  final AuthService _authService = AuthService();

  bool _isLoading = false;
  bool _isLoggedIn = false;
  Map<String, dynamic>? _user;

  bool get isLoading => _isLoading;
  bool get isLoggedIn => _isLoggedIn;
  Map<String, dynamic>? get user => _user;

  Future<void> checkAuthStatus() async {
    final loggedIn = await StorageService.isLoggedIn();
    if (loggedIn) {
      try {
        _user = await _authService.getUser();
        _isLoggedIn = true;
      } catch (_) {
        await StorageService.deleteToken();
        _isLoggedIn = false;
      }
    }
    notifyListeners();
  }

  Future<String?> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();
    try {
      final data = await _authService.login(email: email, password: password);
      _user = data['user'];
      _isLoggedIn = true;
      _isLoading = false;
      notifyListeners();
      return null;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return e.toString().replaceFirst('Exception: ', '');
    }
  }

  Future<String?> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    _isLoading = true;
    notifyListeners();
    try {
      final data = await _authService.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
      );
      _user = data['user'];
      _isLoggedIn = true;
      _isLoading = false;
      notifyListeners();
      return null;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return e.toString().replaceFirst('Exception: ', '');
    }
  }

  Future<String?> forgotPassword(String email) async {
    _isLoading = true;
    notifyListeners();
    try {
      await _authService.forgotPassword(email);
      _isLoading = false;
      notifyListeners();
      return null;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return e.toString().replaceFirst('Exception: ', '');
    }
  }

  Future<void> logout() async {
    await _authService.logout();
    _user = null;
    _isLoggedIn = false;
    notifyListeners();
  }
}
