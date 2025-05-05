import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../config/api_config.dart';

class Product {
  final int id;
  final String name;
  final String description;
  final double price;
  final String? image;
  final int stock;
  final int categoryId;
  final String brand;
  final String status;
  final bool isActive;

  Product({
    required this.id,
    required this.name,
    required this.description,
    required this.price,
    this.image,
    required this.stock,
    required this.categoryId,
    required this.brand,
    required this.status,
    required this.isActive,
  });

  factory Product.fromJson(Map<String, dynamic> json) {
    print('Parsing product: ${json['name']}'); // Debug log
    print('Image path: ${json['image']}'); // Debug log
    return Product(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      price: double.parse(json['price'].toString()),
      image: json['image'],
      stock: json['stock'],
      categoryId: json['category_id'],
      brand: json['brand'],
      status: json['status'],
      isActive: json['is_active'],
    );
  }

  String get imageUrl {
    if (image == null || image!.isEmpty) {
      print('Using placeholder image for product: $name'); // Debug log
      return 'https://via.placeholder.com/300x300?text=No+Image';
    }
    
    // If the image is a full URL, return it as is
    if (image!.startsWith('http')) {
      print('Using full URL for product: $name - $image'); // Debug log
      return image!;
    }
    
    // For local images, construct the full URL
    final baseUrl = ApiConfig.baseUrl;
    final imageUrl = '$baseUrl/storage/$image';
    print('Constructed image URL for product: $name - $imageUrl'); // Debug log
    return imageUrl;
  }
}

class ProductProvider extends ChangeNotifier {
  List<Product> _products = [];
  bool _isLoading = false;
  String _error = '';

  List<Product> get products => _products;
  bool get isLoading => _isLoading;
  String get error => _error;

  Future<void> fetchProducts() async {
    try {
      _isLoading = true;
      notifyListeners();

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/api/products'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      ).timeout(const Duration(seconds: 10));

      print('Fetch products response status: ${response.statusCode}'); // Debug log
      print('Fetch products response body: ${response.body}'); // Debug log

      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body);
        _products = data.map((json) => Product.fromJson(json)).toList();
        print('Successfully loaded ${_products.length} products'); // Debug log
        for (var product in _products) {
          print('Product: ${product.name} - Image URL: ${product.imageUrl}'); // Debug log
        }
      } else {
        print('Error fetching products: ${response.statusCode}'); // Debug log
        throw Exception('Failed to load products');
      }
    } catch (e) {
      print('Error in fetchProducts: $e'); // Debug log
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
} 