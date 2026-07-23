import 'package:dio/dio.dart';
import '../services/api_client.dart';
import '../services/storage_service.dart';

class AuthService {
  final Dio _dio = ApiClient().dio;

  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    try {
      final res = await _dio.post('/auth/login', data: {
        'email': email,
        'password': password,
      });

      final data = res.data['data'];
      await StorageService.saveToken(data['token']);
      return data;
    } on DioException catch (e) {
      if (e.response?.statusCode == 422) {
        final errors = e.response?.data['errors'] ?? {};
        final msg = errors.values.first?.first ?? 'Invalid credentials';
        throw Exception(msg);
      }
      throw Exception(e.response?.data['message'] ?? 'Login failed');
    }
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final res = await _dio.post('/auth/register', data: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      });

      final data = res.data['data'];
      await StorageService.saveToken(data['token']);
      return data;
    } on DioException catch (e) {
      if (e.response?.statusCode == 422) {
        final errors = e.response?.data['errors'] ?? {};
        final msg = errors.values.first?.first ?? 'Validation failed';
        throw Exception(msg);
      }
      throw Exception(e.response?.data['message'] ?? 'Registration failed');
    }
  }

  Future<void> forgotPassword(String email) async {
    try {
      await _dio.post('/auth/forgot-password', data: {'email': email});
    } on DioException catch (e) {
      if (e.response?.statusCode == 422) {
        final errors = e.response?.data['errors'] ?? {};
        final msg = errors.values.first?.first ?? 'Invalid email';
        throw Exception(msg);
      }
      throw Exception(e.response?.data['message'] ?? 'Request failed');
    }
  }

  Future<Map<String, dynamic>> getUser() async {
    try {
      final res = await _dio.get('/auth/user');
      return res.data['data'];
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Failed to fetch user');
    }
  }

  Future<void> logout() async {
    try {
      await _dio.post('/auth/logout');
    } catch (_) {}
    await StorageService.deleteToken();
  }
}
