import 'package:shared_preferences/shared_preferences.dart';

class StorageService {
  static SharedPreferences? _prefs;

  static Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  static Future<void> saveToken(String token) async {
    await _prefs?.setString('auth_token', token);
  }

  static Future<String?> getToken() async {
    return _prefs?.getString('auth_token');
  }

  static Future<void> deleteToken() async {
    await _prefs?.remove('auth_token');
  }

  static Future<void> saveUser(Map<String, dynamic> user) async {
    await _prefs?.setString('user_data', user.toString());
  }

  static Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }
}
