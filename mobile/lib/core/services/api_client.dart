import 'dart:convert';
import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import '../constants/api_config.dart';
import 'storage_service.dart';

class ApiClient {
  static final ApiClient _instance = ApiClient._internal();
  factory ApiClient() => _instance;

  late final Dio dio;

  ApiClient._internal() {
    dio = Dio(
      BaseOptions(
        baseUrl: ApiConfig.baseUrl,
        connectTimeout: const Duration(milliseconds: ApiConfig.connectTimeout),
        receiveTimeout: const Duration(milliseconds: ApiConfig.receiveTimeout),
        headers: ApiConfig.defaultHeaders,
        responseType: ResponseType.json,
      ),
    );

    dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await StorageService.getToken();
          if (token != null && token.isNotEmpty) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          handler.next(options);
        },
        onError: (e, handler) {
          final message = _extractErrorMessage(e);
          if (kDebugMode) {
            print('API Error [${e.response?.statusCode}]: $message');
            print('Request: ${e.requestOptions.method} ${e.requestOptions.path}');
            if (e.response?.data != null) {
              print('Response: ${e.response!.data}');
            }
          }
          final modifiedError = DioException(
            requestOptions: e.requestOptions,
            response: e.response,
            type: e.type,
            error: e.error,
            message: message,
          );
          handler.next(modifiedError);
        },
      ),
    );
  }

  static String _extractErrorMessage(DioException e) {
    if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.sendTimeout ||
        e.type == DioExceptionType.receiveTimeout) {
      return 'Connection timed out. Please check your internet and try again.';
    }
    if (e.type == DioExceptionType.connectionError) {
      return 'Cannot connect to server. Please check your internet connection.';
    }
    if (e.type == DioExceptionType.cancel) {
      return 'Request was cancelled.';
    }
    if (e.type == DioExceptionType.unknown) {
      return 'Something went wrong. Please try again.';
    }

    final data = e.response?.data;
    if (data == null) return 'Unexpected error occurred (${e.response?.statusCode}).';

    Map<String, dynamic>? json;
    if (data is Map<String, dynamic>) {
      json = data;
    } else if (data is String) {
      try {
        final decoded = jsonDecode(data);
        if (decoded is Map<String, dynamic>) json = decoded;
      } catch (_) {
        return data.isNotEmpty ? data : 'Error ${e.response?.statusCode}';
      }
    }

    if (json == null) return 'Error ${e.response?.statusCode}';

    // 1. "message" field (Laravel standard)
    final message = json['message'] as String?;
    if (message != null && message.isNotEmpty) return message;

    // 2. "error" field
    final error = json['error'] as String?;
    if (error != null && error.isNotEmpty) return error;

    // 3. Laravel validation errors - "errors" object
    final errors = json['errors'];
    if (errors != null) {
      if (errors is Map) {
        final allMessages = <String>[];
        errors.forEach((key, value) {
          if (value is List) {
            for (final v in value) {
              allMessages.add(v.toString());
            }
          } else {
            allMessages.add(value.toString());
          }
        });
        if (allMessages.isNotEmpty) return allMessages.join('\n');
      } else if (errors is String) {
        return errors;
      }
    }

    // 4. "data" with message
    final dataField = json['data'];
    if (dataField is Map<String, dynamic>) {
      final msg = dataField['message'] as String?;
      if (msg != null && msg.isNotEmpty) return msg;
    }

    return 'Error ${e.response?.statusCode}';
  }
}
