import 'package:flutter/material.dart';
import '../services/discover_service.dart';
import '../../models/app_models.dart';

class DiscoverProvider extends ChangeNotifier {
  final DiscoverService _service = DiscoverService();

  List<UserProfile> _profiles = [];
  bool _loading = false;
  String? _error;

  List<UserProfile> get profiles => _profiles;
  bool get loading => _loading;
  String? get error => _error;

  Future<void> loadProfiles() async {
    _loading = true;
    _error = null;
    notifyListeners();
    try {
      final data = await _service.getProfiles();
      _profiles = data.map((j) => UserProfile.fromJson(j)).toList();
    } catch (e) {
      _error = e.toString().replaceFirst('Exception: ', '');
    }
    _loading = false;
    notifyListeners();
  }

  Future<bool> swipe(String swipedId, String action) async {
    try {
      final matched = await _service.swipe(swipedId, action);
      _profiles.removeWhere((p) => p.id == swipedId);
      notifyListeners();
      return matched;
    } catch (e) {
      return false;
    }
  }
}
