class ApiConfig {
  static const String baseUrl = 'http://192.168.1.136:8001/api';
  static const int connectTimeout = 15000;
  static const int receiveTimeout = 15000;

  static const Map<String, String> defaultHeaders = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };
}
