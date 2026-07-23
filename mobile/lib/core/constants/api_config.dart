class ApiConfig {
  static const String baseUrl = 'http://10.0.2.2:8000/api';
  static const int connectTimeout = 15000;
  static const int receiveTimeout = 15000;

  static const Map<String, String> defaultHeaders = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };
}
