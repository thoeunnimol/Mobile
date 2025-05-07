import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/product_provider.dart';
import '../providers/category_provider.dart';
import '../main.dart';

class ShopScreen extends StatefulWidget {
  const ShopScreen({super.key});

  @override
  State<ShopScreen> createState() => _ShopScreenState();
}

class _ShopScreenState extends State<ShopScreen> {
  @override
  void initState() {
    super.initState();
    // Fetch products when the screen is first loaded
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<ProductProvider>().fetchProducts();
      context.read<CategoryProvider>().fetchCategories();
    });
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final productProvider = context.watch<ProductProvider>();
       final categoryProvider = context.watch<CategoryProvider>();
    
    return Scaffold(
     appBar: AppBar(
        title: TextField(
          decoration: InputDecoration(
            hintText: 'Search products...',
            hintStyle: TextStyle(color: Colors.white70),
            border: InputBorder.none,
            prefixIcon: Icon(Icons.search, color: Colors.white70),
          ),
          style: TextStyle(color: Colors.white),
          onChanged: (query) {
            // TODO: Implement search functionality
            print('Search query: $query');
          },
        ),
        backgroundColor: theme.colorScheme.primary,
      ),
      body: CustomScrollView(
        slivers: [

          // Categories Section
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Categories',
                    style: theme.textTheme.titleMedium,
                  ),
                  const SizedBox(height: 16),
                  if (categoryProvider.isLoading)
                    const Center(child: CircularProgressIndicator())
                  else if (categoryProvider.error.isNotEmpty)
                    Center(child: Text(categoryProvider.error))
                  else
                    SizedBox(
                      height: 100,
                      child: ListView(
                        scrollDirection: Axis.horizontal,
                        children: categoryProvider.categories.map((category) {
                          return Container(
                            width: 100,
                            margin: const EdgeInsets.only(right: 16),
                            decoration: BoxDecoration(
                              color: theme.colorScheme.primary.withOpacity(0.1),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                ClipRRect(
                                  borderRadius: BorderRadius.circular(8),
                                  child: Image.network(
                                    category.imageUrl,
                                    width: 40,
                                    height: 40,
                                    fit: BoxFit.cover,
                                    errorBuilder: (context, error, stackTrace) {
                                      return Icon(
                                        Icons.category,
                                        size: 40,
                                        color: theme.colorScheme.primary,
                                      );
                                    },
                                  ),
                                ),
                                const SizedBox(height: 8),
                                Text(
                                  category.name,
                                  style: theme.textTheme.bodyMedium?.copyWith(
                                    fontWeight: FontWeight.bold,
                                  ),
                                  textAlign: TextAlign.center,
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ],
                            ),
                          );
                        }).toList(),
                      ),
                    ),
                ],
              ),
            ),
          ),
          // Products Grid
          SliverPadding(
            padding: const EdgeInsets.all(16.0),
            sliver: SliverGrid(
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                childAspectRatio: 0.75,
                crossAxisSpacing: 16,
                mainAxisSpacing: 16,
              ),
              delegate: SliverChildBuilderDelegate(
                (context, index) {
                  if (productProvider.isLoading) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  if (productProvider.error.isNotEmpty) {
                    return Center(child: Text(productProvider.error));
                  }
                  final product = productProvider.products[index];
                  // In the products grid section
                  return ProductCard(
                    productName: product.name,
                    price: '\$${product.price.toStringAsFixed(2)}',
                    imageUrl: product.imageUrl,
                    product: product, // Add this line
                  );
                },
                childCount: productProvider.isLoading
                    ? 1
                    : productProvider.error.isNotEmpty
                        ? 1
                        : productProvider.products.length,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCategoryCard(BuildContext context, String title, IconData icon) {
    return Container(
      width: 100,
      margin: const EdgeInsets.only(right: 16),
      decoration: BoxDecoration(
        color: Theme.of(context).colorScheme.primary.withOpacity(0.1),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            icon,
            size: 32,
            color: Theme.of(context).colorScheme.primary,
          ),
          const SizedBox(height: 8),
          Text(
            title,
            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }
}