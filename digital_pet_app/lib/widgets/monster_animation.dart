import 'dart:async';
import 'dart:math';
import 'package:flutter/material.dart';

class MonsterAnimation extends StatefulWidget {
  final String imagePath;

  const MonsterAnimation({
    Key? key,
    this.imagePath = 'assets/images/test.png',
  }) : super(key: key);

  @override
  MonsterAnimationState createState() => MonsterAnimationState();
}

class MonsterAnimationState extends State<MonsterAnimation> with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;
  Timer? _pauseTimer;
  Random random = Random();

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      duration: const Duration(seconds: 1),
      vsync: this,
    );

    _animation = Tween(begin: -100.0, end: 100.0).animate(
      CurvedAnimation(
        parent: _controller,
        curve: Curves.linear,
      ),
    )..addListener(() {
      setState(() {});
    });

    startAnimation();
  }

  void startAnimation() {
    _controller.repeat(reverse: true);
    setRandomPause();
  }

  void setRandomPause() {
    _pauseTimer?.cancel();
    _pauseTimer = Timer(Duration(seconds: random.nextInt(1) + 1), () {
      if (_controller.isAnimating) {
        _controller.stop();
        setRandomPause();
      } else {
        _controller.repeat(reverse: true);
        setRandomPause();
      }
    });
  }

  @override
  void dispose() {
    _controller.dispose();
    _pauseTimer?.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Transform.translate(
      offset: Offset(_animation.value, 0),
      child: Image.asset(
        widget.imagePath,
        width: 50,
        height: 50,
      ),
    );
  }
}
