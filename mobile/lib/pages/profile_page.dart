import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../screens/auth/login_screen.dart';
import '../screens/auth/register_screen.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final authProvider = Provider.of<AuthProvider>(context);
    
    return Scaffold(
      body: CustomScrollView(
        slivers: [
          // Profile Header
          SliverAppBar(
            expandedHeight: 200,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              background: Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [
                      theme.colorScheme.primary,
                      theme.colorScheme.primary.withOpacity(0.8),
                    ],
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                  ),
                ),
                child: Stack(
                  children: [
                    Positioned.fill(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          CircleAvatar(
                            radius: 50,
                            backgroundColor: Colors.white.withOpacity(0.2),
                            child: CircleAvatar(
                              radius: 46,
                              backgroundImage: authProvider.isAuthenticated && 
                                  authProvider.user != null &&
                                  authProvider.user?['profileImage'] != null
                                      ? NetworkImage(authProvider.user!['profileImage']!)
                                      : null,
                              child: authProvider.isAuthenticated && 
                                  (authProvider.user == null || 
                                   authProvider.user?['profileImage'] == null)
                                      ? const Icon(Icons.person, size: 40)
                                      : null,
                            ),
                          ),
                          const SizedBox(height: 16),
                          Text(
                            authProvider.isAuthenticated 
                                ? (authProvider.user?['name'] as String?) ?? 'User'
                                : 'Guest User',
                            style: theme.textTheme.headlineSmall?.copyWith(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 8),
                          Text(
                            authProvider.isAuthenticated
                                ? (authProvider.user?['email'] as String?) ?? ''
                                : 'Sign in to your account',
                            style: theme.textTheme.bodyMedium?.copyWith(
                              color: Colors.white.withOpacity(0.9),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),

          // Profile Sections
          SliverList(
            delegate: SliverChildListDelegate([
              // Show login/register buttons if not authenticated
              if (!authProvider.isAuthenticated)
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    children: [
                      ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(builder: (context) => const LoginScreen()),
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          minimumSize: const Size(double.infinity, 48),
                        ),
                        child: const Text('Login'),
                      ),
                      const SizedBox(height: 12),
                      OutlinedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(builder: (context) => const RegisterScreen()),
                          );
                        },
                        style: OutlinedButton.styleFrom(
                          minimumSize: const Size(double.infinity, 48),
                        ),
                        child: const Text('Register'),
                      ),
                    ],
                  ),
                ),

              // Only show these sections if authenticated
              if (authProvider.isAuthenticated) ...[
                // Customer Information Section
                _buildSection(
                  context,
                  'Customer Information',
                  [
                    ListTile(
                      leading: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: Theme.of(context).colorScheme.primary.withOpacity(0.1),
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          Icons.person,
                          color: Theme.of(context).colorScheme.primary,
                        ),
                      ),
                      title: Text(
                        'Name: ${authProvider.user?['name'] ?? 'Not provided'}',
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                    ),
                    ListTile(
                      leading: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: Theme.of(context).colorScheme.primary.withOpacity(0.1),
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          Icons.phone,
                          color: Theme.of(context).colorScheme.primary,
                        ),
                      ),
                      title: Text(
                        'Phone: ${authProvider.user?['phone'] ?? 'Not provided'}',
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                    ),
                    ListTile(
                      leading: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: Theme.of(context).colorScheme.primary.withOpacity(0.1),
                          shape: BoxShape.circle,
                        ),
                        child: Icon(
                          Icons.email,
                          color: Theme.of(context).colorScheme.primary,
                        ),
                      ),
                      title: Text(
                        'Email: ${authProvider.user?['email'] ?? 'Not provided'}',
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                    ),
                    _buildMenuItem(
                      context,
                      'Customer Orders',
                      Icons.shopping_bag,
                      () {},
                    ),
                  ],
                ),

              
                // Settings Section
                _buildSection(
                  context,
                  'Settings',
                  [
                    _buildMenuItem(
                      context,
                      'Edit Profile',
                      Icons.edit,
                      () {},
                    ),
                    _buildMenuItem(
                      context,
                      'Shipping Address',
                      Icons.location_on,
                      () {},
                    ),
                    _buildMenuItem(
                      context,
                      'Payment Methods',
                      Icons.payment,
                      () {},
                    ),
                    _buildMenuItem(
                      context,
                      'Notifications',
                      Icons.notifications,
                      () {},
                    ),
                  ],
                ),

                // Support Section
                _buildSection(
                  context,
                  'Support',
                  [
                    _buildMenuItem(
                      context,
                      'Help Center',
                      Icons.help,
                      () {},
                    ),
                    _buildMenuItem(
                      context,
                      'Contact Us',
                      Icons.contact_support,
                      () {},
                    ),
                    _buildMenuItem(
                      context,
                      'About Us',
                      Icons.info,
                      () {},
                    ),
                  ],
                ),

                const SizedBox(height: 32),
                // Logout Button
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16.0),
                  child: ElevatedButton(
                    onPressed: () {
                      authProvider.logout();
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text('Logged out successfully'),
                        ),
                      );
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.red,
                      foregroundColor: Colors.white,
                      minimumSize: const Size(double.infinity, 48),
                    ),
                    child: const Text('Logout'),
                  ),
                ),
              ],
              const SizedBox(height: 32),
            ]),
          ),
        ],
      ),
    );
  }

  Widget _buildSection(BuildContext context, String title, List<Widget> items) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 24, 16, 8),
          child: Text(
            title,
            style: Theme.of(context).textTheme.titleLarge?.copyWith(
              fontWeight: FontWeight.bold,
              color: Theme.of(context).colorScheme.primary,
            ),
          ),
        ),
        Card(
          margin: const EdgeInsets.symmetric(horizontal: 16),
          child: Column(
            children: items,
          ),
        ),
        const SizedBox(height: 16),
      ],
    );
  }

  Widget _buildMenuItem(
    BuildContext context,
    String title,
    IconData icon,
    VoidCallback onTap,
  ) {
    return ListTile(
      leading: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: Theme.of(context).colorScheme.primary.withOpacity(0.1),
          shape: BoxShape.circle,
        ),
        child: Icon(
          icon,
          color: Theme.of(context).colorScheme.primary,
        ),
      ),
      title: Text(
        title,
        style: Theme.of(context).textTheme.bodyLarge,
      ),
      trailing: Icon(
        Icons.chevron_right,
        color: Theme.of(context).colorScheme.onSurface.withOpacity(0.5),
      ),
      onTap: onTap,
      contentPadding: const EdgeInsets.symmetric(horizontal: 16),
    );
  }
}