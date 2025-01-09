package thedigialex.digitalpet.model.entities
import com.google.gson.annotations.SerializedName

data class UserTrainingEquipment(
    val id: Int,
    @SerializedName("user_id") val userId: Int,
    @SerializedName("training_equipment_id") val trainingEquipmentId: Int,
    @SerializedName("stat_increase") val statIncrease: Int,
    val level: Int,
    @SerializedName("training_equipment") val trainingEquipment: TrainingEquipment
)