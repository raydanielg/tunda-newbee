import 'package:dio/dio.dart';
import '../services/api_client.dart';

class MatchService {
  final Dio _dio = ApiClient().dio;

  Future<List<Map<String, dynamic>>> getMatches() async {
    try {
      final res = await _dio.get('/matches');
      final list = res.data['data'] as List;
      return list.cast<Map<String, dynamic>>();
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to load matches');
    }
  }
}
