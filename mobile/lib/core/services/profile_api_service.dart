import 'package:dio/dio.dart';
import '../services/api_client.dart';

class ProfileApiService {
  final Dio _dio = ApiClient().dio;

  Future<Map<String, dynamic>> getProfile() async {
    try {
      final res = await _dio.get('/profile');
      return res.data['data'] as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to load profile');
    }
  }

  Future<Map<String, dynamic>> updateProfile(Map<String, dynamic> data) async {
    try {
      final res = await _dio.patch('/profile', data: data);
      return res.data['data'] as Map<String, dynamic>;
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to update profile');
    }
  }
}
