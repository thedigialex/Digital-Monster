package thedigialex.digitalpet.model.entities

data class DigitalMonster(
    val id: Int,
    val eggId: Int,
    val monsterId: Int,
    val spriteSheet: String,
    val stage: String,
    val minWeight: Int,
    val maxEnergy: Int,
    val requiredEvoPoints: Int
)
