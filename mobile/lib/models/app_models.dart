class UserProfile {
  final String id;
  final String name;
  final int age;
  final String bio;
  final String occupation;
  final String education;
  final String region;
  final double distance;
  final List<String> interests;
  final String relationshipGoal;
  final bool verified;
  final bool online;
  final String? avatar;
  final String? gender;

  UserProfile({
    required this.id,
    required this.name,
    required this.age,
    required this.bio,
    required this.occupation,
    required this.education,
    required this.region,
    required this.distance,
    required this.interests,
    required this.relationshipGoal,
    this.verified = false,
    this.online = false,
    this.avatar,
    this.gender,
  });

  factory UserProfile.fromJson(Map<String, dynamic> j) {
    return UserProfile(
      id: j['id']?.toString() ?? '',
      name: j['name'] ?? '',
      age: j['age'] ?? 0,
      bio: j['bio'] ?? '',
      occupation: j['occupation'] ?? '',
      education: j['education'] ?? '',
      region: j['region'] ?? '',
      distance: (j['distance'] ?? 0).toDouble(),
      interests: (j['interests'] as List?)?.map((e) => e.toString()).toList() ?? [],
      relationshipGoal: j['relationship_goal'] ?? 'Dating',
      verified: j['verified'] ?? false,
      online: j['online'] ?? false,
      avatar: j['avatar'],
      gender: j['gender'],
    );
  }
}

class ChatMessage {
  final String id;
  final String senderId;
  final String text;
  final DateTime time;
  final bool isMe;
  final bool read;

  ChatMessage({
    required this.id,
    required this.senderId,
    required this.text,
    required this.time,
    required this.isMe,
    this.read = false,
  });

  factory ChatMessage.fromJson(Map<String, dynamic> j) {
    return ChatMessage(
      id: j['id']?.toString() ?? '',
      senderId: j['sender_id']?.toString() ?? '',
      text: j['text'] ?? '',
      time: j['time'] != null ? DateTime.parse(j['time']) : DateTime.now(),
      isMe: j['is_me'] ?? false,
      read: j['read'] ?? false,
    );
  }
}

class Match {
  final UserProfile user;
  final DateTime matchedAt;
  final String? lastMessage;
  final DateTime? lastMessageTime;
  final int unreadCount;
  final String? conversationId;

  Match({
    required this.user,
    required this.matchedAt,
    this.lastMessage,
    this.lastMessageTime,
    this.unreadCount = 0,
    this.conversationId,
  });

  factory Match.fromJson(Map<String, dynamic> j) {
    return Match(
      user: UserProfile.fromJson(j['user'] as Map<String, dynamic>),
      matchedAt: j['matched_at'] != null ? DateTime.parse(j['matched_at']) : DateTime.now(),
      lastMessage: j['last_message'],
      lastMessageTime: j['last_message_time'] != null ? DateTime.parse(j['last_message_time']) : null,
      unreadCount: j['unread_count'] ?? 0,
      conversationId: j['conversation_id']?.toString(),
    );
  }
}

class AppNotification {
  final String id;
  final String type;
  final String title;
  final String body;
  final DateTime time;
  final bool read;

  AppNotification({
    required this.id,
    required this.type,
    required this.title,
    required this.body,
    required this.time,
    this.read = false,
  });

  factory AppNotification.fromJson(Map<String, dynamic> j) {
    return AppNotification(
      id: j['id']?.toString() ?? '',
      type: j['type'] ?? 'system',
      title: j['title'] ?? '',
      body: j['body'] ?? '',
      time: j['time'] != null ? DateTime.parse(j['time']) : DateTime.now(),
      read: j['read'] ?? false,
    );
  }
}
