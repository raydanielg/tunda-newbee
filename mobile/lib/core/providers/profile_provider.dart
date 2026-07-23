import 'package:flutter/material.dart';
import '../services/profile_api_service.dart';

class ProfileProvider extends ChangeNotifier {
  final ProfileApiService _service = ProfileApiService();

  Map<String, dynamic>? _profile;
  bool _loading = false;
  String? _error;

  Map<String, dynamic>? get profile => _profile;
  bool get loading => _loading;
  String? get error => _error;

  String get name => _profile?['name'] ?? 'User';
  String get email => _profile?['email'] ?? '';
  String get region => _profile?['region'] ?? '';
  bool get isVerified => _profile?['profile']?['is_verified'] ?? false;
  bool get isPremium => _profile?['profile']?['is_premium'] ?? false;
  int get matchesCount => _profile?['stats']?['matches'] ?? 0;
  int get likesCount => _profile?['stats']?['likes'] ?? 0;
  int get photosCount => _profile?['stats']?['photos'] ?? 0;
  String get bio => _profile?['profile']?['bio'] ?? '';
  String get occupation => _profile?['profile']?['occupation'] ?? '';
  List<String> get interests => (_profile?['interests'] as List?)?.map((e) => e.toString()).toList() ?? [];

  Future<void> loadProfile() async {
    _loading = true;
    _error = null;
    notifyListeners();
    try {
      _profile = await _service.getProfile();
    } catch (e) {
      _error = e.toString().replaceFirst('Exception: ', '');
    }
    _loading = false;
    notifyListeners();
  }

  Future<String?> updateProfile(Map<String, dynamic> data) async {
    _loading = true;
    notifyListeners();
    try {
      _profile = await _service.updateProfile(data);
      _loading = false;
      notifyListeners();
      return null;
    } catch (e) {
      _loading = false;
      notifyListeners();
      return e.toString().replaceFirst('Exception: ', '');
    }
  }
}
