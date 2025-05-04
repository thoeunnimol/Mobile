import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/product.dart';

class ProductService {
  static const String baseUrl = 'https://fakestoreapi.com';

  Future<List<Product>> getProducts() async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/products'));
      
      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body);
        return data.map((json) => Product.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load products');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }

  Future<List<Product>> getProductsByCategory(String category) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/products/category/$category'),
      );
      
      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body);
        return data.map((json) => Product.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load products by category');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }

  Future<Product> getProductById(int id) async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/products/$id'));
      
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return Product.fromJson(data);
      } else {
        throw Exception('Failed to load product');
      }
    } catch (e) {
      throw Exception('Error: $e');
    }
  }
} 