import 'package:dio/dio.dart';
import '../services/api_client.dart';

class MessageService {
  final Dio _dio = ApiClient().dio;

  Future<List<Map<String, dynamic>>> getConversations() async {
    try {
      final res = await _dio.get('/conversations');
      final list = res.data['data'] as List;
      return list.cast<Map<String, dynamic>>();
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to load conversations');
    }
  }

  Future<List<Map<String, dynamic>>> getMessages(String conversationId) async {
    try {
      final res = await _dio.get('/conversations/$conversationId/messages');
      final list = res.data['data'] as List;
      return list.cast<Map<String, dynamic>>();
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to load messages');
    }
  }

  Future<Map<String, dynamic>> sendMessage(String conversationId, String body) async {
    try {
      final res = await _dio.post('/conversations/$conversationId/send', data: {'body': body});
      return res.data['data'] as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to send message');
    }
  }
}
