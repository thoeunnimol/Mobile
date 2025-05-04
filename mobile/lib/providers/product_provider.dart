import 'package:flutter/foundation.dart';
import '../models/product.dart';
import '../services/product_service.dart';

class ProductProvider with ChangeNotifier {
  final ProductService _productService = ProductService();
  List<Product> _products = [];
  List<Product> _filteredProducts = [];
  String _selectedCategory = 'all';
  bool _isLoading = false;
  String _error = '';

  List<Product> get products => _products;
  List<Product> get filteredProducts => _filteredProducts;
  String get selectedCategory => _selectedCategory;
  bool get isLoading => _isLoading;
  String get error => _error;

  List<String> get categories {
    final allCategories = _products.map((p) => p.category).toSet().toList();
    allCategories.insert(0, 'all');
    return allCategories;
  }

  Future<void> fetchProducts() async {
    _isLoading = true;
    _error = '';
    notifyListeners();

    try {
      _products = await _productService.getProducts();
      _filteredProducts = _products;
    } catch (e) {
      _error = e.toString();
    }

    _isLoading = false;
    notifyListeners();
  }

  void filterByCategory(String category) {
    _selectedCategory = category;
    if (category == 'all') {
      _filteredProducts = _products;
    } else {
      _filteredProducts = _products.where((product) => product.category == category).toList();
    }
    notifyListeners();
  }
} 