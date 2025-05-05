import 'package:flutter/foundation.dart' show kIsWeb;
import 'dart:io' show Platform;

class ApiConfig {
  // For web development
  static const String webBaseUrl = 'http://127.0.0.1:8000';
  
  // For Android emulator
  static const String androidBaseUrl = 'http://10.0.2.2:8000';
  
  // For iOS simulator
  static const String iosBaseUrl = 'http://127.0.0.1:8000';

  // Get the appropriate base URL based on the platform
  static String get baseUrl {
    if (kIsWeb) {
      return webBaseUrl;
    } else if (Platform.isAndroid) {
      return androidBaseUrl;
    } else if (Platform.isIOS) {
      return iosBaseUrl;
    }
    return webBaseUrl; // Default fallback
  }
} 