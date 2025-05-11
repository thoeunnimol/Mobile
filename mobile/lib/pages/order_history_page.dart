import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class OrderHistoryScreen extends StatelessWidget {
  const OrderHistoryScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    
    return Scaffold(
      appBar: AppBar(title: const Text('My Orders')),
      body: authProvider.isAuthenticated
          ? FutureBuilder(
              future: authProvider.fetchUserOrders(),
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }

                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }

                return Consumer<AuthProvider>(
                  builder: (context, auth, _) {
                    if (auth.userOrders.isEmpty) {
                      return const Center(child: Text('You have no orders yet'));
                    }

                    return RefreshIndicator(
                      onRefresh: () => authProvider.fetchUserOrders(),
                      child: ListView.builder(
                        padding: const EdgeInsets.all(8),
                        itemCount: auth.userOrders.length,
                        itemBuilder: (context, index) {
                          final order = auth.userOrders[index];
                          return OrderListItem(order: order);
                        },
                      ),
                    );
                  },
                );
              },
            )
          : const Center(
              child: Text('Please login to view your orders'),
            ),
    );
  }
}

class OrderListItem extends StatelessWidget {
  final Map<String, dynamic> order;
  
  const OrderListItem({super.key, required this.order});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 8, horizontal: 16),
      child: ListTile(
        title: Text('Order #${order['id']}'),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Total: \$${order['total']}'),
            Text('Date: ${order['date'] ?? 'N/A'}'),
          ],
        ),
        trailing: const Icon(Icons.chevron_right),
        onTap: () {
          // Navigate to order details
        },
      ),
    );
  }
}