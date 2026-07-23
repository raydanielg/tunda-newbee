import 'package:dio/dio.dart';
import '../services/api_client.dart';

class NotificationService {
  final Dio _dio = ApiClient().dio;

  Future<List<Map<String, dynamic>>> getNotifications() async {
    try {
      final res = await _dio.get('/notifications');
      final list = res.data['data'] as List;
      return list.cast<Map<String, dynamic>>();
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to load notifications');
    }
  }

  Future<void> markRead(String id) async {
    try {
      await _dio.patch('/notifications/$id/read');
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to mark read');
    }
  }

  Future<void> markAllRead() async {
    try {
      await _dio.patch('/notifications/read-all');
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to mark all read');
    }
  }
}
