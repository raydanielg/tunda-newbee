import 'package:flutter/material.dart';
import '../services/match_service.dart';
import '../../models/app_models.dart';

class MatchProvider extends ChangeNotifier {
  final MatchService _service = MatchService();

  List<Match> _matches = [];
  bool _loading = false;
  String? _error;

  List<Match> get matches => _matches;
  bool get loading => _loading;
  String? get error => _error;

  Future<void> loadMatches() async {
    _loading = true;
    _error = null;
    notifyListeners();
    try {
      final data = await _service.getMatches();
      _matches = data.map((j) => Match.fromJson(j)).toList();
    } catch (e) {
      _error = e.toString().replaceFirst('Exception: ', '');
    }
    _loading = false;
    notifyListeners();
  }
}
