import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../core/constants/app_colors.dart';
import '../../../models/dummy_data.dart';

class ActivityScreen extends StatelessWidget {
  const ActivityScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final notifs = DummyData.notifications;

    return Scaffold(
      backgroundColor: AppColors.bgLight,
      appBar: AppBar(
        title: Text(
          'Activity',
          style: GoogleFonts.nunito(
            fontWeight: FontWeight.w800,
            fontSize: 20,
          ),
        ),
        backgroundColor: AppColors.white,
        foregroundColor: AppColors.maroon400,
        elevation: 0,
        centerTitle: false,
        actions: [
          TextButton(
            onPressed: () {},
            child: Text(
              'Mark all read',
              style: GoogleFonts.nunito(
                fontSize: 13,
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
        ],
      ),
      body: SafeArea(
        child: ListView.builder(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          itemCount: notifs.length,
          itemBuilder: (ctx, i) {
            final n = notifs[i];
            return Container(
              margin: const EdgeInsets.only(bottom: 10),
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: n.read ? AppColors.white : AppColors.maroon50,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(
                  color: n.read ? AppColors.gray100 : AppColors.maroon100,
                ),
              ),
              child: Row(
                children: [
                  Container(
                    width: 44,
                    height: 44,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: _iconColor(n.type).withOpacity(0.1),
                    ),
                    child: Icon(
                      _iconForType(n.type),
                      size: 22,
                      color: _iconColor(n.type),
                    ),
                  ),
                  const SizedBox(width: 14),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              n.title,
                              style: GoogleFonts.nunito(
                                fontSize: 14,
                                fontWeight: FontWeight.w700,
                                color: AppColors.gray700,
                              ),
                            ),
                            if (!n.read)
                              Container(
                                width: 8,
                                height: 8,
                                decoration: const BoxDecoration(
                                  color: AppColors.maroon400,
                                  shape: BoxShape.circle,
                                ),
                              ),
                          ],
                        ),
                        const SizedBox(height: 2),
                        Text(
                          n.body,
                          style: GoogleFonts.nunito(
                            fontSize: 13,
                            color: AppColors.gray500,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          _formatTime(n.time),
                          style: GoogleFonts.nunito(
                            fontSize: 11,
                            color: AppColors.gray400,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }

  IconData _iconForType(String type) {
    switch (type) {
      case 'match':
        return Icons.favorite;
      case 'message':
        return Icons.chat_bubble;
      case 'like':
        return Icons.thumb_up;
      case 'super_like':
        return Icons.star;
      case 'verification':
        return Icons.verified;
      default:
        return Icons.notifications;
    }
  }

  Color _iconColor(String type) {
    switch (type) {
      case 'match':
        return AppColors.maroon400;
      case 'message':
        return AppColors.maroon300;
      case 'like':
        return AppColors.maroon400;
      case 'super_like':
        return AppColors.amber500;
      case 'verification':
        return Colors.green;
      default:
        return AppColors.gray400;
    }
  }

  String _formatTime(DateTime time) {
    final diff = DateTime.now().difference(time);
    if (diff.inMinutes < 60) return '${diff.inMinutes}m ago';
    if (diff.inHours < 24) return '${diff.inHours}h ago';
    return '${diff.inDays}d ago';
  }
}
