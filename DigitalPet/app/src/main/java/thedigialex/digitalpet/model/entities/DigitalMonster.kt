package thedigialex.digitalpet.model.entities

import com.google.gson.annotations.SerializedName

data class DigitalMonster(
    @SerializedName("egg_id")
    val eggId: Int,

    @SerializedName("monster_id")
    val monsterId: Int,

    @SerializedName("sprite_sheet")
    val spriteSheet: String,

    @SerializedName("stage")
    val stage: String,

    @SerializedName("min_weight")
    val minWeight: Int,

    @SerializedName("max_energy")
    val maxEnergy: Int,

    @SerializedName("required_evo_points")
    val requiredEvoPoints: Int
)
