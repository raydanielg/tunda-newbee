import 'package:flutter/material.dart';
import '../services/message_service.dart';
import '../../models/app_models.dart';

class MessageProvider extends ChangeNotifier {
  final MessageService _service = MessageService();

  List<Match> _conversations = [];
  List<ChatMessage> _messages = [];
  bool _loading = false;
  String? _error;

  List<Match> get conversations => _conversations;
  List<ChatMessage> get messages => _messages;
  bool get loading => _loading;
  String? get error => _error;

  Future<void> loadConversations() async {
    _loading = true;
    _error = null;
    notifyListeners();
    try {
      final data = await _service.getConversations();
      _conversations = data.map((j) => Match.fromJson(j)).toList();
    } catch (e) {
      _error = e.toString().replaceFirst('Exception: ', '');
    }
    _loading = false;
    notifyListeners();
  }

  Future<void> loadMessages(String conversationId) async {
    _loading = true;
    _error = null;
    notifyListeners();
    try {
      final data = await _service.getMessages(conversationId);
      _messages = data.map((j) => ChatMessage.fromJson(j)).toList();
    } catch (e) {
      _error = e.toString().replaceFirst('Exception: ', '');
    }
    _loading = false;
    notifyListeners();
  }

  Future<void> sendMessage(String conversationId, String body) async {
    try {
      final data = await _service.sendMessage(conversationId, body);
      _messages.add(ChatMessage.fromJson(data));
      notifyListeners();
    } catch (e) {
      _error = e.toString().replaceFirst('Exception: ', '');
      notifyListeners();
    }
  }
}
