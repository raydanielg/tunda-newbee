import 'package:dio/dio.dart';
import '../services/api_client.dart';
import '../services/storage_service.dart';

class DiscoverService {
  final Dio _dio = ApiClient().dio;

  Future<List<Map<String, dynamic>>> getProfiles() async {
    try {
      final res = await _dio.get('/discover');
      final list = res.data['data'] as List;
      return list.cast<Map<String, dynamic>>();
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to load profiles');
    }
  }

  Future<bool> swipe(String swipedId, String action) async {
    try {
      final res = await _dio.post('/swipe', data: {
        'swiped_id': int.parse(swipedId),
        'action': action,
      });
      return res.data['data']['matched'] ?? false;
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Swipe failed');
    }
  }
}
