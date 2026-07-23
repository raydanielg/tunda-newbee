import 'package:flutter/material.dart';
import '../services/notification_service.dart';
import '../../models/app_models.dart';

class NotificationProvider extends ChangeNotifier {
  final NotificationService _service = NotificationService();

  List<AppNotification> _notifications = [];
  bool _loading = false;
  String? _error;

  List<AppNotification> get notifications => _notifications;
  bool get loading => _loading;
  String? get error => _error;

  int get unreadCount => _notifications.where((n) => !n.read).length;

  Future<void> loadNotifications() async {
    _loading = true;
    _error = null;
    notifyListeners();
    try {
      final data = await _service.getNotifications();
      _notifications = data.map((j) => AppNotification.fromJson(j)).toList();
    } catch (e) {
      _error = e.toString().replaceFirst('Exception: ', '');
    }
    _loading = false;
    notifyListeners();
  }

  Future<void> markAllRead() async {
    try {
      await _service.markAllRead();
      for (var n in _notifications) {
        n = AppNotification(id: n.id, type: n.type, title: n.title, body: n.body, time: n.time, read: true);
      }
      notifyListeners();
    } catch (_) {}
  }
}
