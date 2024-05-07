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

  List<Map<String, String>> images = [
    {'on': 'assets/images/image1_on.png', 'off': 'assets/images/test.png'},
    {'on': 'assets/images/image2_on.png', 'off': 'assets/images/test.png'},
    {'on': 'assets/images/image3_on.png', 'off': 'assets/images/test.png'},
    {'on': 'assets/images/image4_on.png', 'off': 'assets/images/test.png'},
    {'on': 'assets/images/image5_on.png', 'off': 'assets/images/test.png'},
    {'on': 'assets/images/image6_on.png', 'off': 'assets/images/test.png'},
    {'on': 'assets/images/image7_on.png', 'off': 'assets/images/test.png'},
    {'on': 'assets/images/image8_on.png', 'off': 'assets/images/test.png'},
  ];

  HSLColor lightGrey = const HSLColor.fromAHSL(1.0, 240, 0.02, 0.75);

  @override
  void initState() {
    super.initState();
    loadMonsterDetails();
    updateImages();
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
                      border: Border.all(color: Colors.green, width: 3),
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
                        const SizedBox(
                          height: 100.0,
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                            children: [
                              MonsterAnimation(
                                imagePath: 'assets/images/test.png'
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
                        onPressed: () => changeImage(-1),
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
                        onPressed: () {
                          print("Bottom Button pressed");
                        },
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


