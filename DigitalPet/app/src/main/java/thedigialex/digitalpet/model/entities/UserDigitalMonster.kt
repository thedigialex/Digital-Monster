package thedigialex.digitalpet.model.entities

import com.google.gson.annotations.SerializedName

data class UserDigitalMonster(
    val id: Int,
    @SerializedName("user_id") val userId: Int,
    @SerializedName("digital_monster_id") val digitalMonsterId: Int,
    val isMain: Boolean,
    val name: String,
    val type: String,
    val age: Int,
    val level: Int,
    val exp: Int,
    val strength: Int,
    val agility: Int,
    val defense: Int,
    val mind: Int,
    val hunger: Int,
    val exercise: Int,
    val clean: Int,
    val weight: Int,
    val energy: Int,
    val wins: Int,
    val losses: Int,
    val trainings: Int,
    @SerializedName("care_misses") val careMisses: Int,
    @SerializedName("created_at") val createdAt: String,
    @SerializedName("updated_at") val updatedAt: String
)