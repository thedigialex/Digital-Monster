class DigitalMonster {
  final int id;
  final String name;
  final String type;
  final int level;
  final int eggId;
  final int monsterId;
  final int userId;
  final int exp;
  final int strength;
  final int agility;
  final int defense;
  final int mind;
  final int age;
  final int weight;
  final int minWeight;
  final String? stage;
  final int hunger;
  final int exercise;
  final int clean;
  final int energy;
  final int minEnergy;
  final int wins;
  final int losses;
  final int trainings;
  final int careMisses;

  DigitalMonster({
    required this.id,
    required this.name,
    required this.type,
    required this.level,
    required this.eggId,
    required this.monsterId,
    required this.userId,
    required this.exp,
    required this.strength,
    required this.agility,
    required this.defense,
    required this.mind,
    required this.age,
    required this.weight,
    required this.minWeight,
    this.stage,
    required this.hunger,
    required this.exercise,
    required this.clean,
    required this.energy,
    required this.minEnergy,
    required this.wins,
    required this.losses,
    required this.trainings,
    required this.careMisses,
  });

  factory DigitalMonster.fromJson(Map<String, dynamic> json) {
    return DigitalMonster(
      id: json['id'],
      name: json['name'],
      type: json['type'],
      level: json['level'],
      eggId: json['egg_id'],
      monsterId: json['monster_id'],
      userId: json['user_id'],
      exp: json['exp'],
      strength: json['strength'],
      agility: json['agility'],
      defense: json['defense'],
      mind: json['mind'],
      age: json['age'],
      weight: json['weight'],
      minWeight: json['min_weight'],
      stage: json['stage'],
      hunger: json['hunger'],
      exercise: json['exercise'],
      clean: json['clean'],
      energy: json['energy'],
      minEnergy: json['min_energy'],
      wins: json['wins'],
      losses: json['losses'],
      trainings: json['trainings'],
      careMisses: json['care_misses'],
    );
  }
}
