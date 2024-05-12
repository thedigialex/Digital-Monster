import 'package:flutter/material.dart';
import '/utils/secure_storage.dart';
import '/services/digital_monster_service.dart';
import '/models/digital_monster.dart';
import '/widgets/pushable_button.dart';
import '/widgets/monster_animation.dart';

class DigitalMonsterScreen extends StatefulWidget {
  const DigitalMonsterScreen({super.key});

  @override
  DigitalMonsterScreenState createState() => DigitalMonsterScreenState();
}

class DigitalMonsterScreenState extends State<DigitalMonsterScreen> {
  final SecureStorage storage = SecureStorage();
  final DigitalMonsterService monsterService = DigitalMonsterService();
  String monsterName = '';
  int menuCount = -1;
  bool menuOpen = false;

  List<Map<String, String>> images = [
    {'on': 'assets/images/menu_icons/menu_zero_full.png', 'off': 'assets/images/menu_icons/menu_zero_empty.png'},
    {'on': 'assets/images/menu_icons/menu_one_full.png', 'off': 'assets/images/menu_icons/menu_one_empty.png'},
    {'on': 'assets/images/menu_icons/menu_two_full.png', 'off': 'assets/images/menu_icons/menu_two_empty.png'},
    {'on': 'assets/images/menu_icons/menu_three_full.png', 'off': 'assets/images/menu_icons/menu_three_empty.png'},
    {'on': 'assets/images/menu_icons/menu_four_full.png', 'off': 'assets/images/menu_icons/menu_four_empty.png'},
    {'on': 'assets/images/menu_icons/menu_five_full.png', 'off': 'assets/images/menu_icons/menu_five_empty.png'},
    {'on': 'assets/images/menu_icons/menu_six_full.png', 'off': 'assets/images/menu_icons/menu_six_empty.png'},
    {'on': 'assets/images/menu_icons/menu_seven_full.png', 'off': 'assets/images/menu_icons/menu_seven_empty.png'},
  ];

  HSLColor lightGrey = const HSLColor.fromAHSL(1.0, 240, 0.02, 0.75);

  @override
  void initState() {
    super.initState();
    loadMonsterDetails();
    updateImages();
  }

  void cancelButton() {
    setState(() {
      if(menuOpen) {
        menuOpen = false;
      }
      else{
        menuCount = -1;
        updateImages();
      }
    });
  }

  void changeImage(int movement) {
    setState(() {
      menuCount += movement;
      if (menuCount < 0) {
        menuCount = images.length - 1;
      } else if (menuCount >= images.length) {
        menuCount = 0;
      }
      updateImages();
    });
  }

  void updateImages() {
    setState(() {
      for (int i = 0; i < images.length; i++) {
        images[i]['current'] = ((menuCount == i) ? images[i]['on'] : images[i]['off'])!;
      }
    });
  }

  void loadMonsterDetails() async {
    String? token = await storage.getToken();
    if (token != null) {
      try {
        DigitalMonster? monster = await monsterService.fetchDigitalMonster(token);
        if (mounted) {
          setState(() {
            monsterName = monster != null ? "assets/images/${monster.name}.png" : "assets/images/no_monster.png";
          });
        }
      } catch (e) {
        if (mounted) {
          setState(() {
            monsterName = "assets/images/fetch_error.png";
          });
        }
      }
    } else {
      setState(() {
        monsterName = "assets/images/auth_required.png";
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Digital Monster Details'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: MediaQuery.of(context).size.width * 0.95,
              decoration: BoxDecoration(
                color: Colors.pink[100],
                border: Border.all(color: Colors.black, width: 5),
              ),
              child: Column(
                children: [
                  Container(
                    margin: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: Colors.blueAccent,
                      border: Border.all(color: lightGrey.toColor(), width: 8),
                    ),
                    child: Column(
                      children: [
                        const SizedBox(height: 20),
                        SizedBox(
                          height: 50.0,
                          child: Row(
                            children: [
                              Expanded(
                                child: Image.asset(images[0]['current']!, fit: BoxFit.contain),
                              ),
                              Expanded(
                                child: Image.asset(images[1]['current']!, fit: BoxFit.contain),
                              ),
                              Expanded(
                                child: Image.asset(images[2]['current']!, fit: BoxFit.contain),
                              ),
                              Expanded(
                                child: Image.asset(images[3]['current']!, fit: BoxFit.contain),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 20),
                        SizedBox(
                          height: 100.0,
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                            children: [
                              MonsterAnimation(
                                imagePath: monsterName,
                              )
                            ],
                          ),
                        ),
                        const SizedBox(height: 20),
                        SizedBox(
                          height: 50.0,
                          child: Row(
                            children: [
                              Expanded(
                                child: Image.asset(images[4]['current']!, fit: BoxFit.contain),
                              ),
                              Expanded(
                                child: Image.asset(images[5]['current']!, fit: BoxFit.contain),
                              ),
                              Expanded(
                                child: Image.asset(images[6]['current']!, fit: BoxFit.contain),
                              ),
                              Expanded(
                                child: Image.asset(images[7]['current']!, fit: BoxFit.contain),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 20),
                      ],
                    ),
                  ),
                  Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      PushableButton(
                        height: 50,
                        hslColor: lightGrey,
                        onPressed: () => cancelButton(),
                      ),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          PushableButton(
                            height: 50,
                            hslColor: lightGrey,
                            onPressed: () => changeImage(-1),
                          ),
                          const SizedBox(width: 75),
                          PushableButton(
                            height: 50,
                            hslColor: lightGrey,
                            onPressed: () => changeImage(1),
                          ),
                        ],
                      ),
                      PushableButton(
                        height: 50,
                        hslColor: lightGrey,
                        onPressed: () => cancelButton(),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
