import 'package:flutter/material.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    
    return Scaffold(
      body: CustomScrollView(
        slivers: [
          // Profile Header
          SliverAppBar(
            expandedHeight: 200,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              background: Container(
                color: theme.colorScheme.primary,
                child: Stack(
                  children: [
                    Positioned(
                      top: 50,
                      left: 0,
                      right: 0,
                      child: Column(
                        children: [
                          const CircleAvatar(
                            radius: 50,
                            backgroundImage: NetworkImage('https://i.pravatar.cc/150'),
                          ),
                          const SizedBox(height: 16),
                          Text(
                            'John Doe',
                            style: theme.textTheme.titleLarge?.copyWith(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            'john.doe@example.com',
                            style: theme.textTheme.bodyMedium?.copyWith(
                              color: Colors.white70,
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
              // Orders Section
              _buildSection(
                context,
                'My Orders',
                [
                  _buildMenuItem(
                    context,
                    'Pending Orders',
                    Icons.pending_actions,
                    () {},
                  ),
                  _buildMenuItem(
                    context,
                    'Completed Orders',
                    Icons.check_circle,
                    () {},
                  ),
                  _buildMenuItem(
                    context,
                    'Cancelled Orders',
                    Icons.cancel,
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
                  onPressed: () {},
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.red,
                    foregroundColor: Colors.white,
                    minimumSize: const Size(double.infinity, 48),
                  ),
                  child: const Text('Logout'),
                ),
              ),
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
          padding: const EdgeInsets.all(16.0),
          child: Text(
            title,
            style: Theme.of(context).textTheme.titleMedium?.copyWith(
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        ...items,
        const Divider(),
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
      leading: Icon(icon),
      title: Text(title),
      trailing: const Icon(Icons.chevron_right),
      onTap: onTap,
    );
  }
} 