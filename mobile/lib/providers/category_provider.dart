import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../config/api_config.dart';

class Category {
  final int id;
  final String name;
  final String? image;
  final String? description;
  final bool isActive;

  Category({
    required this.id,
    required this.name,
    this.image,
    this.description,
    required this.isActive,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    print('Parsing category: ${json['name']}'); // Debug log
    return Category(
      id: json['id'],
      name: json['name'],
      image: json['image'],
      description: json['description'],
      isActive: json['is_active'] ?? true,
    );
  }

  String get imageUrl {
    if (image == null || image!.isEmpty) {
      print('Using placeholder image for category: $name'); // Debug log
      return 'https://via.placeholder.com/100x100?text=${Uri.encodeComponent(name)}';
    }
    
    if (image!.startsWith('http')) {
      print('Using full URL for category: $name - $image'); // Debug log
      return image!;
    }
    
    final baseUrl = ApiConfig.baseUrl;
    final imageUrl = '$baseUrl/storage/$image';
    print('Constructed image URL for category: $name - $imageUrl'); // Debug log
    return imageUrl;
  }
}

class CategoryProvider extends ChangeNotifier {
  List<Category> _categories = [];
  bool _isLoading = false;
  String _error = '';

  List<Category> get categories => _categories;
  bool get isLoading => _isLoading;
  String get error => _error;

  Future<void> fetchCategories() async {
    try {
      _isLoading = true;
      notifyListeners();

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/api/categories'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      ).timeout(const Duration(seconds: 10));

      print('Fetch categories response status: ${response.statusCode}'); // Debug log
      print('Fetch categories response body: ${response.body}'); // Debug log

      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body);
        _categories = data.map((json) => Category.fromJson(json)).toList();
        print('Successfully loaded ${_categories.length} categories'); // Debug log
      } else {
        print('Error fetching categories: ${response.statusCode}'); // Debug log
        throw Exception('Failed to load categories');
      }
    } catch (e) {
      print('Error in fetchCategories: $e'); // Debug log
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
} 